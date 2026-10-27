@extends('layouts.admin')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding-bottom: 2rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 900; color: #0F172A; margin: 0;">Edit Kategori #{{ $category->id }}</h1>
            <span style="font-size: 0.82rem; color: #64748B;">Perbarui nama kategori dan tampilan banner.</span>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn-admin btn-secondary" style="font-size: 0.8rem; border-radius: 8px;">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="admin-card" style="border-radius: 16px;">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #334155; text-transform: uppercase; margin-bottom: 0.4rem;">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required style="width: 100%; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.75rem; color: #0F172A; font-weight: 700; outline: none;">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #334155; text-transform: uppercase; margin-bottom: 0.4rem;">Gambar / Icon Kategori</label>
                @if($category->image)
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; padding: 0.6rem; background: #F8FAFC; border-radius: 10px; border: 1px solid #E2E8F0;">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #CBD5E1;">
                        <div>
                            <span style="font-size: 0.8rem; font-weight: 700; color: #0F172A; display: block;">Gambar Saat Ini</span>
                            <span style="font-size: 0.72rem; color: #64748B;">Upload file baru di bawah jika ingin mengganti</span>
                        </div>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" style="width: 100%; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.6rem; color: #0F172A; font-weight: 600; font-size: 0.85rem;">
                <span style="font-size: 0.75rem; color: #64748B; display: block; margin-top: 0.35rem;">Format: JPG, PNG, WEBP, atau SVG (Maks. 2MB). Kosongkan bila tidak diubah.</span>
            </div>

            <div style="margin-bottom: 1.75rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #334155; text-transform: uppercase; margin-bottom: 0.4rem;">Color / Gradient CSS Banner</label>
                <input type="text" name="banner_color" value="{{ old('banner_color', $category->banner_color) }}" style="width: 100%; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.75rem; color: #0F172A; font-weight: 700; outline: none;">
            </div>

            <button type="submit" class="btn-admin btn-yellow" style="width: 100%; justify-content: center; padding: 0.85rem; background: #0F172A; color: #FFFFFF; border-radius: 10px; font-weight: 800;">
                <i class="fa-solid fa-floppy-disk" style="color: #22C55E;"></i> Perbarui Kategori
            </button>
        </form>
    </div>
</div>
@endsection
