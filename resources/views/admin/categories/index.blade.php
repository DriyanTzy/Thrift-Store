@extends('layouts.admin')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.85rem; font-weight: 900; margin-bottom: 0.25rem; color: #0F172A;">Kelola Kategori Produk</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Tambah, edit, dan atur kategori barang marketplace Ruang Lemari.</p>
    </div>

    <a href="{{ route('admin.categories.create') }}" class="btn-admin btn-yellow" style="background: #0F172A; color: #FFFFFF;">
        <i class="fa-solid fa-plus" style="color: #22C55E;"></i> Tambah Kategori Baru
    </a>
</div>

<div class="admin-card" style="padding: 0; overflow: hidden; border-radius: 16px;">
    <div style="overflow-x: auto;">
        <table class="sc-table" style="margin-top: 0;">
            <thead>
                <tr>
                    <th style="padding-left: 1.5rem; width: 35%;">Kategori Produk</th>
                    <th style="width: 25%;">Slug URL</th>
                    <th style="width: 15%;">Total Produk</th>
                    <th style="width: 15%;">Warna Banner</th>
                    <th style="text-align: right; padding-right: 1.5rem; width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                    <tr>
                        <td style="padding-left: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                @if($cat->image)
                                    <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" style="width: 38px; height: 38px; border-radius: 8px; object-fit: cover; border: 1px solid #E2E8F0; flex-shrink: 0;">
                                @else
                                    <div style="width: 38px; height: 38px; border-radius: 8px; background: #F1F5F9; border: 1px solid #E2E8F0; display: flex; align-items: center; justify-content: center; color: #94A3B8; flex-shrink: 0;">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>
                                @endif
                                <div>
                                    <span style="font-weight: 800; color: #0F172A; font-size: 0.92rem; display: block;">
                                        {{ $cat->name }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td style="color: #64748B; font-family: monospace; font-size: 0.85rem;">{{ $cat->slug }}</td>
                        <td>
                            <span style="font-weight: 800; color: #2563EB; background: rgba(59,130,246,0.1); padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.78rem;">
                                {{ $cat->products_count }} Produk
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 20px; height: 20px; border-radius: 6px; background: {{ $cat->banner_color ?: '#0F172A' }}; border: 1px solid #CBD5E1;"></div>
                                <span style="font-size: 0.75rem; color: #64748B;">{{ Str::limit($cat->banner_color ?: '-', 20) }}</span>
                            </div>
                        </td>
                        <td style="text-align: right; padding-right: 1.5rem;">
                            <div style="display: inline-flex; align-items: center; gap: 0.4rem;">
                                <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn-admin btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; border-radius: 8px;">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ addslashes($cat->name) }}?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-admin btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; border-radius: 8px;">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 3rem 1.5rem;">
                            <i class="fa-solid fa-layer-group fa-2x" style="color: #CBD5E1; margin-bottom: 0.75rem; display: block;"></i>
                            <span style="font-weight: 700; font-size: 0.95rem; color: #0F172A; display: block;">Belum ada kategori</span>
                            <span style="font-size: 0.8rem; color: #64748B;">Tambahkan kategori pertama Anda untuk mengelompokkan produk.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
