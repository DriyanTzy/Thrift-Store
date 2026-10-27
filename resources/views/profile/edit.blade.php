@extends('layouts.app')

@section('content')
<div style="max-width: 860px; margin: 0 auto; padding-bottom: 3.5rem;">

    <!-- Breadcrumb -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.75rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #64748B; font-weight: 700;">
            <a href="{{ route('products.index') }}" style="color: #64748B; text-decoration: none;"><i class="fa-solid fa-house"></i> Home</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i>
            <span style="color: #0F172A; font-weight: 800;">Pengaturan Profil</span>
        </div>
        <div style="font-size: 0.8rem; font-weight: 800; color: #16A34A; background: #DCFCE7; padding: 0.3rem 0.85rem; border-radius: 30px;">
            <i class="fa-solid fa-id-card"></i> 1 Email Akun — 2 Profil Terpisah
        </div>
    </div>

    <!-- Header Card with Dual Identity Overview -->
    <div style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); border-radius: 24px; padding: 2rem 2.25rem; color: #FFFFFF; margin-bottom: 2rem; box-shadow: 0 10px 30px rgba(15,23,42,0.15);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <span style="background: rgba(34, 197, 94, 0.2); color: #4ADE80; font-size: 0.72rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: 20px; border: 1px solid rgba(34, 197, 94, 0.35);">
                    <i class="fa-solid fa-user-gear"></i> MANAJEMEN DUA PROFIL
                </span>
                <h1 style="font-size: 1.85rem; font-weight: 900; margin: 0.5rem 0 0.25rem; letter-spacing: -0.5px;">
                    Pengaturan Profil & Identitas
                </h1>
                <p style="color: #94A3B8; font-size: 0.88rem; margin: 0;">
                    Akun terhubung: <strong style="color: #FFF;">{{ $user->email }}</strong>. Anda dapat membedakan profil pribadi untuk belanja dengan profil toko untuk berjualan.
                </p>
            </div>

            <div style="display: flex; gap: 1.25rem; background: rgba(255,255,255,0.06); padding: 0.85rem 1.25rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.1);">
                <div style="text-align: center;">
                    <span style="font-size: 0.7rem; color: #94A3B8; font-weight: 700; text-transform: uppercase;">Profil Pribadi</span>
                    <div style="font-weight: 900; font-size: 0.95rem; color: #FFF; margin-top: 0.15rem;">{{ $user->name }}</div>
                </div>
                <div style="width: 1px; background: rgba(255,255,255,0.15);"></div>
                <div style="text-align: center;">
                    <span style="font-size: 0.7rem; color: #4ADE80; font-weight: 700; text-transform: uppercase;">Profil Toko</span>
                    <div style="font-weight: 900; font-size: 0.95rem; color: #4ADE80; margin-top: 0.15rem;">{{ $user->store_name }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs: Personal Profile vs Store Profile -->
    <div style="display: flex; gap: 0.75rem; margin-bottom: 2rem; border-bottom: 2px solid #E2E8F0; padding-bottom: 0.5rem;">
        <button type="button" 
                onclick="switchProfileTab('personal')" 
                id="tab-btn-personal"
                style="padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 800; font-size: 0.95rem; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; {{ $activeTab === 'personal' ? 'background: #0F172A; color: #FFF;' : 'background: transparent; color: #64748B;' }}">
            <i class="fa-solid fa-user"></i> 1. Profil Pengguna (Pribadi / Pembeli)
        </button>

        <button type="button" 
                onclick="switchProfileTab('store')" 
                id="tab-btn-store"
                style="padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 800; font-size: 0.95rem; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; {{ $activeTab === 'store' ? 'background: #0F172A; color: #FFF;' : 'background: transparent; color: #64748B;' }}">
            <i class="fa-solid fa-store" style="color: #22C55E;"></i> 2. Profil Toko (Penjual / Etalase)
        </button>
    </div>

    <!-- TAB 1: PROFIL PRIBADI PENGGUNA -->
    <div id="tab-content-personal" style="display: {{ $activeTab === 'personal' ? 'block' : 'none' }};">
        <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; padding: 2.25rem; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
            
            <div style="margin-bottom: 1.75rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem;">
                <h3 style="font-size: 1.25rem; font-weight: 900; color: #0F172A; margin: 0;">
                    Data Profil Pengguna
                </h3>
                <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.25rem; margin-bottom: 0;">
                    Identitas ini akan tampil saat Anda berbelanja, menulis ulasan barang, dan melakukan percakapan sebagai pembeli.
                </p>
            </div>

            <form action="{{ route('profile.update-user') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Foto Profil (PP) Pengguna - Max 5MB --}}
                <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 1.75rem; flex-wrap: wrap;">
                    <div style="position: relative; width: 88px; height: 88px; border-radius: 50%; overflow: hidden; background: #0F172A; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(15,23,42,0.15); border: 3px solid #FFFFFF; flex-shrink: 0;">
                        <img id="avatar-preview-img" 
                             src="{{ $user->avatar ? asset('storage/' . $user->avatar) : '' }}" 
                             alt="{{ $user->name }}" 
                             style="width: 100%; height: 100%; object-fit: cover; display: {{ $user->avatar ? 'block' : 'none' }};">
                        <span id="avatar-preview-initial" 
                              style="font-size: 2rem; font-weight: 900; color: #FFFFFF; display: {{ $user->avatar ? 'none' : 'block' }};">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>

                    <div style="flex: 1; min-width: 240px;">
                        <label style="display: block; font-weight: 800; font-size: 0.95rem; color: #0F172A; margin-bottom: 0.25rem;">
                            <i class="fa-solid fa-camera" style="color: #3B82F6;"></i> Foto Profil (Avatar / PP)
                        </label>
                        <p style="font-size: 0.8rem; color: #64748B; margin-bottom: 0.75rem;">
                            Upload foto profil terbaik Anda. Format: JPG, PNG, GIF, atau WEBP. <strong style="color: #16A34A;">Maksimal 5MB</strong>.
                        </p>
                        
                        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                            <label for="avatar-input" style="background: #0F172A; color: #FFFFFF; font-weight: 700; font-size: 0.8rem; padding: 0.5rem 1.1rem; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; transition: background 0.2s;">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Pilih File Foto Profil (Max 5MB)
                            </label>
                            <input type="file" id="avatar-input" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp" style="display: none;" onchange="previewAvatar(event)">
                            <span id="avatar-filename" style="font-size: 0.8rem; color: #64748B; font-weight: 600;">Belum ada file baru dipilih</span>
                        </div>
                        @error('avatar')
                            <div style="color: #EF4444; font-size: 0.8rem; font-weight: 700; margin-top: 0.4rem;">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                            Nama Lengkap Pengguna <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               style="width: 100%; box-sizing: border-box; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 700; color: #0F172A; font-size: 0.9rem; outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                            Alamat Email (1 Akun Terdaftar)
                        </label>
                        <input type="email" value="{{ $user->email }}" readonly
                               style="width: 100%; box-sizing: border-box; background: #EEF2F6; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 700; color: #64748B; font-size: 0.9rem; outline: none; cursor: not-allowed;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                            Nomor WhatsApp / HP Pribadi
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890"
                               style="width: 100%; box-sizing: border-box; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 700; color: #0F172A; font-size: 0.9rem; outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                            ID Pengguna
                        </label>
                        <input type="text" name="player_tag" value="{{ old('player_tag', $user->player_tag) }}" placeholder="Contoh: #RUANGLEMARI99"
                               style="width: 100%; box-sizing: border-box; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 700; color: #0F172A; font-size: 0.9rem; outline: none;">
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                        Alamat Pengiriman Utama
                    </label>
                    <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap pengiriman pesanan Anda..."
                              style="width: 100%; box-sizing: border-box; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 600; color: #0F172A; font-size: 0.9rem; outline: none; font-family: inherit;">{{ old('address', $user->address) }}</textarea>
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                        Bio / Catatan Pribadi
                    </label>
                    <input type="text" name="bio" value="{{ old('bio', $user->bio) }}" placeholder="Contoh: Kolektor fashion vintage & sneakers 90s"
                           style="width: 100%; box-sizing: border-box; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 600; color: #0F172A; font-size: 0.9rem; outline: none;">
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" style="background: #0F172A; color: #FFFFFF; font-weight: 800; font-size: 0.9rem; padding: 0.85rem 2rem; border-radius: 12px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 14px rgba(15,23,42,0.25);">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Profil Pribadi
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- TAB 2: PROFIL TOKO PENJUAL -->
    <div id="tab-content-store" style="display: {{ $activeTab === 'store' ? 'block' : 'none' }};">
        <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; padding: 2.25rem; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 900; color: #0F172A; margin: 0;">
                        Identitas Profil Toko Penjual
                    </h3>
                    <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.25rem; margin-bottom: 0;">
                        Nama toko, logo, kota, dan kontak ini yang dilihat oleh calon pembeli di katalog produk dan halaman etalase toko.
                    </p>
                </div>

                <a href="{{ route('shop.store', $user->id) }}" target="_blank" style="background: #F1F5F9; color: #0F172A; font-weight: 800; font-size: 0.8rem; padding: 0.5rem 1rem; border-radius: 10px; text-decoration: none; border: 1px solid #CBD5E1; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <i class="fa-solid fa-eye" style="color: #22C55E;"></i> Lihat Tampilan Toko Publik
                </a>
            </div>

            <form action="{{ route('profile.update-store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Logo & Banner Toko - Max 5MB --}}
                <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; margin-bottom: 2rem;">
                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 1.75rem; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap;">
                        <div style="width: 84px; height: 84px; border-radius: 16px; overflow: hidden; background: #0F172A; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(15,23,42,0.15); border: 2px solid #CBD5E1; flex-shrink: 0;">
                            <img id="logo-preview-img" 
                                 src="{{ $user->store_logo ? asset('storage/' . $user->store_logo) : '' }}" 
                                 alt="Logo Toko" 
                                 style="width: 100%; height: 100%; object-fit: cover; display: {{ $user->store_logo ? 'block' : 'none' }};">
                            <span id="logo-preview-icon" style="font-size: 2rem; color: #22C55E; display: {{ $user->store_logo ? 'none' : 'block' }};">
                                <i class="fa-solid fa-store"></i>
                            </span>
                        </div>

                        <div>
                            <label style="display: block; font-weight: 800; font-size: 0.95rem; color: #0F172A; margin-bottom: 0.25rem;">
                                <i class="fa-solid fa-image" style="color: #22C55E;"></i> Logo Toko / Brand
                            </label>
                            <p style="font-size: 0.8rem; color: #64748B; margin-bottom: 0.75rem;">
                                Logo yang ditampilkan di katalog, toko publik, dan header etalase. <strong style="color: #16A34A;">Maksimal 5MB</strong>.
                            </p>
                            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                                <label for="logo-input" style="background: #0F172A; color: #FFFFFF; font-weight: 700; font-size: 0.8rem; padding: 0.5rem 1.1rem; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;">
                                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload Logo Toko (Max 5MB)
                                </label>
                                <input type="file" id="logo-input" name="store_logo" accept="image/jpeg,image/png,image/gif,image/webp" style="display: none;" onchange="previewStoreLogo(event)">
                                <span id="logo-filename" style="font-size: 0.8rem; color: #64748B; font-weight: 600;">Belum ada file baru</span>
                            </div>
                            @error('store_logo')
                                <div style="color: #EF4444; font-size: 0.8rem; font-weight: 700; margin-top: 0.4rem;">
                                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div style="border-top: 1px dashed #CBD5E1; padding-top: 1.25rem;">
                        <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.35rem;">
                            <i class="fa-solid fa-panorama" style="color: #6366F1;"></i> Banner Toko (Header Etalase)
                        </label>
                        <p style="font-size: 0.78rem; color: #64748B; margin-bottom: 0.75rem;">
                            Banner horizontal untuk halaman profil toko Anda. Rekomendasi rasio 3:1. <strong style="color: #16A34A;">Maksimal 5MB</strong>.
                        </p>
                        @if($user->store_banner)
                            <div style="height: 120px; width: 100%; border-radius: 12px; overflow: hidden; margin-bottom: 0.75rem; border: 1.5px solid #CBD5E1;">
                                <img id="banner-preview-img" src="{{ asset('storage/' . $user->store_banner) }}" alt="Banner Toko" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @else
                            <div id="banner-preview-box" style="display: none; height: 120px; width: 100%; border-radius: 12px; overflow: hidden; margin-bottom: 0.75rem; border: 1.5px solid #CBD5E1;">
                                <img id="banner-preview-img" src="" alt="Banner Toko" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @endif
                        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                            <label for="banner-input" style="background: #F1F5F9; color: #0F172A; border: 1.5px solid #CBD5E1; font-weight: 700; font-size: 0.8rem; padding: 0.45rem 1rem; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;">
                                <i class="fa-solid fa-image"></i> Pilih Banner Toko (Max 5MB)
                            </label>
                            <input type="file" id="banner-input" name="store_banner" accept="image/jpeg,image/png,image/gif,image/webp" style="display: none;" onchange="previewStoreBanner(event)">
                            <span id="banner-filename" style="font-size: 0.8rem; color: #64748B; font-weight: 600;">Belum ada file baru</span>
                        </div>
                        @error('store_banner')
                            <div style="color: #EF4444; font-size: 0.8rem; font-weight: 700; margin-top: 0.4rem;">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                            Nama Toko / Brand <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="text" name="store_name" value="{{ old('store_name', $user->store_name) }}" required
                               placeholder="Contoh: Shaw Vintage Goods"
                               style="width: 100%; box-sizing: border-box; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 800; color: #0F172A; font-size: 0.95rem; outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                            Slogan / Tagline Toko
                        </label>
                        <input type="text" name="store_slogan" value="{{ old('store_slogan', $user->store_slogan) }}"
                               placeholder="Contoh: Kurasi Terbaik Jaket & Denim Vintage 90s"
                               style="width: 100%; box-sizing: border-box; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 700; color: #0F172A; font-size: 0.9rem; outline: none;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                            Kota Asal Toko <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="text" name="store_city" value="{{ old('store_city', $user->store_city) }}" required
                               placeholder="Contoh: Jakarta Selatan / Bandung / Surabaya"
                               style="width: 100%; box-sizing: border-box; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 700; color: #0F172A; font-size: 0.9rem; outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                            Kontak / CS Khusus Toko
                        </label>
                        <input type="text" name="store_phone" value="{{ old('store_phone', $user->store_phone) }}"
                               placeholder="Nomor CS Toko (contoh: 081299887766)"
                               style="width: 100%; box-sizing: border-box; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 700; color: #0F172A; font-size: 0.9rem; outline: none;">
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                        Deskripsi Lengkap Toko
                    </label>
                    <textarea name="store_description" rows="3" placeholder="Jelaskan mengenai spesialisasi barang yang Anda jual, garansi keaslian, dan jam operasional..."
                              style="width: 100%; box-sizing: border-box; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 600; color: #0F172A; font-size: 0.9rem; outline: none; font-family: inherit;">{{ old('store_description', $user->store_description) }}</textarea>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" style="background: #22C55E; color: #0F172A; font-weight: 900; font-size: 0.9rem; padding: 0.85rem 2rem; border-radius: 12px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 14px rgba(34,197,94,0.3);">
                        <i class="fa-solid fa-store"></i> Simpan Profil Toko Penjual
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

@push('scripts')
<script>
    function switchProfileTab(tab) {
        document.getElementById('tab-content-personal').style.display = (tab === 'personal') ? 'block' : 'none';
        document.getElementById('tab-content-store').style.display = (tab === 'store') ? 'block' : 'none';

        const btnPersonal = document.getElementById('tab-btn-personal');
        const btnStore = document.getElementById('tab-btn-store');

        if (tab === 'personal') {
            btnPersonal.style.background = '#0F172A';
            btnPersonal.style.color = '#FFFFFF';
            btnStore.style.background = 'transparent';
            btnStore.style.color = '#64748B';
        } else {
            btnStore.style.background = '#0F172A';
            btnStore.style.color = '#FFFFFF';
            btnPersonal.style.background = 'transparent';
            btnPersonal.style.color = '#64748B';
        }
    }

    function checkFileSizeLimit(file, inputEl, nameLabelEl, maxMb = 5) {
        if (!file) return false;
        const maxBytes = maxMb * 1024 * 1024;
        if (file.size > maxBytes) {
            alert(`Ukuran file "${file.name}" melebihi batas maksimal ${maxMb}MB (${(file.size / (1024*1024)).toFixed(2)} MB). Silakan pilih foto dengan ukuran lebih kecil.`);
            inputEl.value = '';
            if (nameLabelEl) nameLabelEl.innerText = 'File melebihi batas 5MB!';
            return false;
        }
        return true;
    }

    function previewAvatar(event) {
        const file = event.target.files[0];
        const input = event.target;
        const label = document.getElementById('avatar-filename');
        if (!checkFileSizeLimit(file, input, label, 5)) return;

        if (file) {
            label.innerText = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('avatar-preview-img');
                const initial = document.getElementById('avatar-preview-initial');
                img.src = e.target.result;
                img.style.display = 'block';
                if (initial) initial.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }

    function previewStoreLogo(event) {
        const file = event.target.files[0];
        const input = event.target;
        const label = document.getElementById('logo-filename');
        if (!checkFileSizeLimit(file, input, label, 5)) return;

        if (file) {
            label.innerText = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('logo-preview-img');
                const icon = document.getElementById('logo-preview-icon');
                img.src = e.target.result;
                img.style.display = 'block';
                if (icon) icon.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }

    function previewStoreBanner(event) {
        const file = event.target.files[0];
        const input = event.target;
        const label = document.getElementById('banner-filename');
        if (!checkFileSizeLimit(file, input, label, 5)) return;

        if (file) {
            label.innerText = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
            const reader = new FileReader();
            reader.onload = function(e) {
                const box = document.getElementById('banner-preview-box');
                const img = document.getElementById('banner-preview-img');
                if (box) box.style.display = 'block';
                if (img) {
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
@endsection
