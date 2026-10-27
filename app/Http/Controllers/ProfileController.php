<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Tampilkan formulir pengaturan profil (Pembedaan Profil Pengguna vs Profil Toko dalam 1 Akun/Email)
     */
    public function edit(Request $request)
    {
        $user = auth()->user();
        $activeTab = $request->query('tab', 'personal'); // 'personal' atau 'store'

        return view('profile.edit', compact('user', 'activeTab'));
    }

    /**
     * Perbarui Profil Pengguna / Pribadi (Data Diri & Pengiriman)
     */
    public function updateUser(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:25',
            'player_tag' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()->route('profile.edit', ['tab' => 'personal'])
            ->with('success', 'Profil pribadi pengguna berhasil disimpan!');
    }

    /**
     * Perbarui Profil Toko / Penjual (Identitas Etalase & Brand Toko)
     */
    public function updateStore(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_slogan' => 'nullable|string|max:255',
            'store_city' => 'required|string|max:100',
            'store_phone' => 'nullable|string|max:25',
            'store_description' => 'nullable|string|max:1500',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'store_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('store_logo')) {
            if ($user->store_logo) {
                Storage::disk('public')->delete($user->store_logo);
            }
            $validated['store_logo'] = $request->file('store_logo')->store('stores', 'public');
        }

        if ($request->hasFile('store_banner')) {
            if ($user->store_banner) {
                Storage::disk('public')->delete($user->store_banner);
            }
            $validated['store_banner'] = $request->file('store_banner')->store('stores', 'public');
        }

        $user->update($validated);

        return redirect()->route('profile.edit', ['tab' => 'store'])
            ->with('success', 'Profil toko penjualan Anda berhasil diperbarui!');
    }
}
