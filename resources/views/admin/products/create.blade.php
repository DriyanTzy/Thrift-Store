@extends('layouts.admin')

@section('content')
<div style="max-width: 700px; margin: 0 auto; padding-bottom: 2rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 900; color: #0F172A; margin: 0;">Tambah Produk Baru</h1>
            <span style="font-size: 0.82rem; color: #64748B;">Daftarkan barang thrifting / fashion ke katalog platform.</span>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn-admin btn-secondary" style="font-size: 0.8rem; border-radius: 8px;">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="admin-card" style="border-radius: 16px;">
        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #334155; text-transform: uppercase; margin-bottom: 0.4rem;">Kategori Produk</label>
                <select name="category_id" required style="width: 100%; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.75rem; color: #0F172A; font-weight: 700; outline: none;">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #334155; text-transform: uppercase; margin-bottom: 0.4rem;">Nama Produk</label>
                <input type="text" name="name" required placeholder="Contoh: Vintage Denim Jacket 90s Original" style="width: 100%; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.75rem; color: #0F172A; font-weight: 700; outline: none;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #334155; text-transform: uppercase; margin-bottom: 0.4rem;">Harga Jual (Rp)</label>
                    <input type="number" name="price" required placeholder="185000" style="width: 100%; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.75rem; color: #0F172A; font-weight: 700; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #334155; text-transform: uppercase; margin-bottom: 0.4rem;">Stok Unit</label>
                    <input type="number" name="stock" value="1" required style="width: 100%; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.75rem; color: #0F172A; font-weight: 700; outline: none;">
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #334155; text-transform: uppercase; margin-bottom: 0.4rem;">Badge Kondisi / Promo (Opsional)</label>
                <input type="text" name="badge" placeholder="Contoh: 9/10 - GOOD CONDITION, RARE VINTAGE" style="width: 100%; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.75rem; color: #0F172A; font-weight: 700; outline: none;">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #334155; text-transform: uppercase; margin-bottom: 0.4rem;">Deskripsi Barang</label>
                <textarea name="description" rows="3" placeholder="Jelaskan detail ukuran (P x L), kondisi bahan, minus (jika ada)..." style="width: 100%; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.75rem; color: #0F172A; font-family: inherit; font-weight: 500; outline: none;"></textarea>
            </div>

            <div style="margin-bottom: 1.75rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #0F172A; font-size: 0.9rem; font-weight: 700;">
                    <input type="checkbox" name="is_active" value="1" checked style="transform: scale(1.2); accent-color: #16A34A;">
                    Tampilkan produk di etalase katalog toko
                </label>
            </div>

            <button type="submit" class="btn-admin btn-yellow" style="width: 100%; justify-content: center; padding: 0.85rem; background: #0F172A; color: #FFFFFF; border-radius: 10px; font-weight: 800;">
                <i class="fa-solid fa-floppy-disk" style="color: #22C55E;"></i> Simpan Produk Baru
            </button>
        </form>
    </div>
</div>
@endsection
