@extends('layouts.admin')

@section('content')
<!-- Compact Top Bar -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.15rem; flex-wrap: wrap; gap: 0.75rem;">
    <div>
        <h1 style="font-size: 1.45rem; font-weight: 900; margin: 0; color: #0F172A; letter-spacing: -0.3px;">Kelola Produk</h1>
        <span style="color: var(--text-muted); font-size: 0.8rem;">Daftar katalog barang & inventaris toko platform Ruang Lemari</span>
    </div>

    <a href="{{ route('admin.products.create') }}" class="btn-admin btn-yellow" style="background: #0F172A; color: #FFFFFF; padding: 0.45rem 0.95rem; font-size: 0.82rem; border-radius: 8px;">
        <i class="fa-solid fa-plus" style="color: #22C55E;"></i> Tambah Produk
    </a>
</div>

<div class="admin-card" style="padding: 0; overflow: hidden; border-radius: 14px;">
    <div style="overflow-x: auto;">
        <table class="sc-table" style="margin-top: 0; font-size: 0.85rem;">
            <thead>
                <tr>
                    <th style="padding-left: 1.25rem; width: 36%;">Produk & Toko</th>
                    <th style="width: 15%;">Kategori</th>
                    <th style="width: 15%;">Harga</th>
                    <th style="width: 10%;">Stok</th>
                    <th style="width: 10%;">Kondisi/Promo</th>
                    <th style="width: 8%; text-align: center;">Status</th>
                    <th style="text-align: right; padding-right: 1.25rem; width: 6%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $prod)
                    @php
                        $firstImage = $prod->images->first()?->image_url ?? $prod->image;
                        $sellerStore = $prod->user?->store_name ?? ($prod->user?->name ?? 'Ruang Lemari');
                    @endphp
                    <tr>
                        <td style="padding-left: 1.25rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <!-- Thumbnail Produk (40x40px presisi) -->
                                <div style="width: 42px; height: 42px; border-radius: 8px; background: #F1F5F9; border: 1.5px solid #E2E8F0; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    @if($firstImage)
                                        @if(str_starts_with($firstImage, 'http'))
                                            <img src="{{ $firstImage }}" alt="{{ $prod->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $prod->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @endif
                                    @else
                                        <i class="fa-solid fa-shirt" style="color: #94A3B8; font-size: 1.1rem;"></i>
                                    @endif
                                </div>

                                <div style="overflow: hidden; max-width: 280px;">
                                    <a href="{{ route('products.show', $prod->slug) }}" target="_blank" style="font-weight: 800; color: #0F172A; text-decoration: none; font-size: 0.88rem; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $prod->name }}">
                                        {{ $prod->name }}
                                    </a>
                                    <span style="font-size: 0.72rem; color: #64748B; display: inline-flex; align-items: center; gap: 0.3rem;">
                                        <i class="fa-solid fa-store" style="color: #22C55E; font-size: 0.68rem;"></i> {{ $sellerStore }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="background: rgba(59,130,246,0.1); color: #2563EB; padding: 0.2rem 0.55rem; border-radius: 20px; font-size: 0.72rem; font-weight: 800; display: inline-block; white-space: nowrap;">
                                {{ $prod->category->name }}
                            </span>
                        </td>
                        <td>
                            <span style="font-weight: 800; color: #0F172A; font-size: 0.9rem; white-space: nowrap;">
                                Rp {{ number_format($prod->price, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <span style="font-weight: 800; font-size: 0.82rem; color: {{ $prod->stock > 5 ? '#16A34A' : ($prod->stock > 0 ? '#D97706' : '#DC2626') }}; white-space: nowrap;">
                                {{ $prod->stock }} pcs
                            </span>
                        </td>
                        <td>
                            @if($prod->badge)
                                <span style="background: #FEE2E2; color: #DC2626; border: 1px solid #FECACA; padding: 0.15rem 0.45rem; border-radius: 6px; font-size: 0.68rem; font-weight: 800; display: inline-block; white-space: nowrap;">
                                    {{ $prod->badge }}
                                </span>
                            @else
                                <span style="color: #CBD5E1; font-size: 0.75rem;">-</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <!-- Status VALID / INVALID (Hemat Layout Tabel) -->
                            <span class="status-badge {{ $prod->is_active ? 'status-valid' : 'status-invalid' }}">
                                {{ $prod->is_active ? 'VALID' : 'INVALID' }}
                            </span>
                        </td>
                        <td style="text-align: right; padding-right: 1.25rem;">
                            <div style="display: inline-flex; align-items: center; gap: 0.35rem;">
                                <a href="{{ route('admin.products.edit', $prod->id) }}" class="btn-admin btn-secondary" style="padding: 0.32rem 0.6rem; font-size: 0.75rem; border-radius: 6px;" title="Edit Produk">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" onsubmit="return confirm('Yakin hapus produk {{ addslashes($prod->name) }}?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-admin btn-danger" style="padding: 0.32rem 0.55rem; font-size: 0.75rem; border-radius: 6px;" title="Hapus Produk">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 3rem 1.5rem;">
                            <i class="fa-solid fa-box-open fa-2x" style="color: #CBD5E1; margin-bottom: 0.75rem; display: block;"></i>
                            <span style="font-weight: 700; font-size: 0.95rem; color: #0F172A; display: block;">Belum ada produk</span>
                            <span style="font-size: 0.8rem; color: #64748B;">Klik tombol 'Tambah Produk' untuk mendaftarkan inventaris.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="pagination-wrapper" style="padding: 0.85rem 1.25rem; border-top: 1px solid var(--border-color); background: #FFFFFF;">
            {{ $products->links() }}
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
