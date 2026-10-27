@extends('layouts.app')

@section('content')
<div style="max-width: 760px; margin: 0 auto; padding-bottom: 3rem;">

    <!-- Top Navigation Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <a href="{{ route('seller.store') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #0F172A; text-decoration: none; font-weight: 800; font-size: 0.9rem; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 0.5rem 1rem; border-radius: 12px; transition: all 0.2s;">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Etalase
        </a>
        <span style="font-size: 0.85rem; font-weight: 800; color: #22C55E; background: rgba(34,197,94,0.1); padding: 0.35rem 0.85rem; border-radius: 30px; border: 1px solid rgba(34,197,94,0.25);">
            <i class="fa-solid fa-store"></i> DITERBITKAN KE ETALASE TOKO SAYA
        </span>
    </div>

    @if ($errors->any())
        <div style="background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; padding: 1rem 1.25rem; border-radius: 14px; margin-bottom: 1.5rem; font-size: 0.875rem;">
            <strong style="display: block; margin-bottom: 0.25rem;"><i class="fa-solid fa-circle-exclamation"></i> Ada kesalahan pada input:</strong>
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Card Container -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 24px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
        
        <div style="margin-bottom: 2rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem;">
            <h1 style="font-size: 1.65rem; font-weight: 900; color: #0F172A; margin: 0; letter-spacing: -0.5px;">
                <i class="fa-solid fa-box-archive" style="color: #0F172A; margin-right: 0.5rem;"></i> Upload Barang Jualan Baru
            </h1>
            <p style="color: #64748B; font-size: 0.9rem; margin-top: 0.25rem;">
                Isi rincian barang thrifting original Anda untuk dipajang langsung di etalase toko.
            </p>
        </div>

        <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" id="upload-form">
            @csrf

            <!-- 1. Foto Barang (Max 5 Foto) -->
            <div style="margin-bottom: 1.75rem;">
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.5rem;">
                    <label style="font-weight: 800; font-size: 0.875rem; color: #0F172A;">
                        <i class="fa-solid fa-camera" style="margin-right: 0.35rem; color: #22C55E;"></i> Foto Barang Jualan <span style="color: #EF4444;">*</span>
                    </label>
                    <span style="font-size: 0.75rem; font-weight: 700; color: #64748B;" id="photo-counter">
                        Maksimal 5 foto (1-5 foto)
                    </span>
                </div>

                <!-- Custom Drag & Drop Box -->
                <div id="drop-area" onclick="document.getElementById('product-images').click();" style="border: 2px dashed #CBD5E1; border-radius: 16px; padding: 1.75rem 1rem; text-align: center; background: #F8FAFC; cursor: pointer; transition: all 0.2s;">
                    <div style="width: 52px; height: 52px; background: rgba(34,197,94,0.1); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #16A34A; font-size: 1.35rem; margin-bottom: 0.5rem;">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>
                    <div style="font-size: 0.95rem; font-weight: 800; color: #0F172A;">Klik untuk memilih foto produk</div>
                    <div style="font-size: 0.8rem; color: #64748B; margin-top: 0.25rem;">Format JPG, PNG, WEBP (Maksimal 5 file, maks 2MB per foto)</div>
                </div>

                <input type="file" id="product-images" name="images[]" multiple accept="image/*" required style="display: none;" onchange="handleImageSelection(this)">

                <!-- Preview Grid (1 to 5 images) -->
                <div id="image-preview-container" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.75rem; margin-top: 1rem;"></div>
            </div>

            <!-- 2. Nama Barang -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 800; font-size: 0.875rem; color: #0F172A; margin-bottom: 0.5rem;">
                    Nama Barang Thrifting <span style="color: #EF4444;">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Nike Vintage Windbreaker 90s Block Color" required
                       style="width: 100%; background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 12px; padding: 0.85rem 1rem; font-size: 0.95rem; font-weight: 700; color: #0F172A; outline: none; transition: border 0.2s;">
            </div>

            <!-- 3. Kategori & Stok Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.875rem; color: #0F172A; margin-bottom: 0.5rem;">
                        Kategori Barang <span style="color: #EF4444;">*</span>
                    </label>
                    <select name="category_id" required style="width: 100%; background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 12px; padding: 0.85rem 1rem; font-size: 0.95rem; font-weight: 700; color: #0F172A; outline: none;">
                        <option value="" disabled selected>Pilih Kategori...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.875rem; color: #0F172A; margin-bottom: 0.5rem;">
                        Jumlah Stok <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="number" name="stock" value="{{ old('stock', 1) }}" min="1" required
                           style="width: 100%; background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 12px; padding: 0.85rem 1rem; font-size: 0.95rem; font-weight: 700; color: #0F172A; outline: none;">
                </div>
            </div>

            <!-- 4. Harga & Badge Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.875rem; color: #0F172A; margin-bottom: 0.5rem;">
                        Harga Jual (Rp) <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price') }}" placeholder="Contoh: 185000" required
                           style="width: 100%; background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 12px; padding: 0.85rem 1rem; font-size: 0.95rem; font-weight: 700; color: #0F172A; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.875rem; color: #0F172A; margin-bottom: 0.5rem;">
                        Badge Kondisi (Keterangan)
                    </label>
                    <input type="text" name="badge" value="{{ old('badge') }}" placeholder="Contoh: 9.5/10 - Like New"
                           style="width: 100%; background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 12px; padding: 0.85rem 1rem; font-size: 0.95rem; font-weight: 700; color: #0F172A; outline: none;">
                </div>
            </div>

            <!-- 5. Opsi Ukuran & Stok per Size (Ready Stok Tiap Size Beda-beda) -->
            <div style="margin-bottom: 1.5rem; background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.35rem;">
                    <label style="font-weight: 800; font-size: 0.9rem; color: #0F172A;">
                        <i class="fa-solid fa-boxes-stacked" style="color: #22C55E; margin-right: 0.35rem;"></i> Stok per Ukuran (Size Ready) <span style="color: #EF4444;">*</span>
                    </label>
                    <span id="calculatedTotalStock" style="font-size: 0.8rem; font-weight: 800; color: #16A34A; background: #DCFCE7; padding: 0.2rem 0.65rem; border-radius: 20px;">
                        Total Stok: 0 Item
                    </span>
                </div>
                <p style="font-size: 0.8rem; color: #64748B; margin-top: 0; margin-bottom: 1rem;">
                    Masukkan jumlah stok untuk masing-masing ukuran yang tersedia (ukuran yang bernilai 0 atau kosong otomatis dianggap habis/tidak ready):
                </p>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 0.85rem;">
                    @foreach(['S', 'M', 'L', 'XL', 'XXL', '28', '30', '32', '34', '40', '41', '42', '43', 'All Size'] as $sz)
                        <div style="background: #FFFFFF; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.75rem 0.85rem; text-align: center; transition: all 0.2s;" onfocusin="this.style.borderColor='#0F172A'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.05)';" onfocusout="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none';">
                            <div style="font-weight: 900; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.35rem;">
                                Size {{ $sz }}
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 0.3rem;">
                                <input type="number" 
                                       name="size_stocks[{{ $sz }}]" 
                                       class="size-stock-input" 
                                       value="0" 
                                       min="0" 
                                       style="width: 100%; text-align: center; font-size: 0.95rem; font-weight: 800; color: #0F172A; background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 8px; padding: 0.35rem; outline: none;" 
                                       oninput="updateTotalStockCalculated()">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 5. Deskripsi & Detail Item -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 800; font-size: 0.875rem; color: #0F172A; margin-bottom: 0.5rem;">
                    Deskripsi & Kondisi Detail Barang <span style="color: #EF4444;">*</span>
                </label>
                <textarea name="description" rows="4" placeholder="Tuliskan ukuran PxL, kondisi kain, zipper, garansi keaslian, dll..." required
                          style="width: 100%; background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 12px; padding: 0.85rem 1rem; font-size: 0.9rem; font-weight: 600; color: #0F172A; outline: none; line-height: 1.6;">{{ old('description') }}</textarea>
            </div>

            <!-- Submit Button -->
            <div style="border-top: 1px solid #F1F5F9; padding-top: 1.5rem; text-align: right;">
                <button type="submit" style="background: #0F172A; color: #FFFFFF; font-weight: 900; font-size: 1rem; padding: 0.95rem 2rem; border-radius: 14px; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(15,23,42,0.25); transition: all 0.2s;" onmouseover="this.style.opacity='0.95';" onmouseout="this.style.opacity='1';">
                    <i class="fa-solid fa-cloud-arrow-up" style="margin-right: 0.5rem;"></i> Terbitkan ke Etalase Toko
                </button>
            </div>

        </form>

    </div>

</div>

@push('scripts')
<script>
    let selectedFiles = [];

    function handleImageSelection(input) {
        const files = Array.from(input.files);
        if (files.length > 5) {
            alert('Maksimal hanya dapat mengunggah 5 foto! Sistem memilih 5 foto pertama.');
            selectedFiles = files.slice(0, 5);
        } else {
            selectedFiles = files;
        }

        renderPreviews();
    }

    function renderPreviews() {
        const container = document.getElementById('image-preview-container');
        const counter = document.getElementById('photo-counter');
        container.innerHTML = '';

        if (selectedFiles.length > 0) {
            counter.textContent = `${selectedFiles.length} dari 5 foto dipilih`;
            counter.style.color = '#16A34A';
        } else {
            counter.textContent = 'Maksimal 5 foto (1-5 foto)';
            counter.style.color = '#64748B';
        }

        selectedFiles.forEach((file, index) => {
            const card = document.createElement('div');
            card.style.cssText = 'position: relative; border-radius: 12px; overflow: hidden; border: 2px solid ' + (index === 0 ? '#22C55E' : '#E2E8F0') + '; background: #0F172A; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center;';

            const img = document.createElement('img');
            img.style.cssText = 'width: 100%; height: 100%; object-fit: cover;';
            img.src = URL.createObjectURL(file);

            const badge = document.createElement('div');
            badge.style.cssText = 'position: absolute; top: 4px; left: 4px; background: rgba(15,23,42,0.85); color: #FFF; font-size: 0.65rem; font-weight: 800; padding: 2px 6px; border-radius: 6px;';
            badge.textContent = index === 0 ? 'Utama' : '#' + (index + 1);

            card.appendChild(img);
            card.appendChild(badge);
            container.appendChild(card);
        });
    }

    function updateTotalStockCalculated() {
        let total = 0;
        document.querySelectorAll('.size-stock-input').forEach(inp => {
            const val = parseInt(inp.value) || 0;
            if (val > 0) total += val;
        });

        const label = document.getElementById('calculatedTotalStock');
        const generalStockInput = document.querySelector('input[name="stock"]');
        if (label) {
            label.textContent = `Total Stok: ${total} Item`;
        }
        if (generalStockInput && total > 0) {
            generalStockInput.value = total;
        }
    }
</script>
@endpush
@endsection
