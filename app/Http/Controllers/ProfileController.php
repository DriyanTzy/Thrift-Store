<?php

namespace App\Http\Controllers;

use App\DTO\PersonalProfileDTO;
use App\DTO\StoreProfileDTO;
use App\Http\Requests\UpdatePersonalProfileRequest;
use App\Http\Requests\UpdateStoreProfileRequest;
use App\Services\UserProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private readonly UserProfileService $userProfileService
    ) {}

    /**
     * Tampilkan formulir pengaturan profil (Pembedaan Profil Pengguna vs Profil Toko dalam 1 Akun/Email)
     */
    public function edit(Request $request): View
    {
        $user = auth()->user();
        $activeTab = $request->query('tab', 'personal');

        return view('profile.edit', compact('user', 'activeTab'));
    }

    /**
     * Perbarui Profil Pengguna / Pribadi via PersonalProfileDTO
     */
    public function updateUser(UpdatePersonalProfileRequest $request): RedirectResponse
    {
        $dto = PersonalProfileDTO::fromArray(
            data: $request->validated(),
            avatar: $request->file('avatar')
        );

        $this->userProfileService->updatePersonalProfile(auth()->user(), $dto);

        return redirect()->route('profile.edit', ['tab' => 'personal'])
            ->with('success', 'Profil pribadi pengguna berhasil disimpan!');
    }

    /**
     * Perbarui Profil Toko / Penjual via StoreProfileDTO
     */
    public function updateStore(UpdateStoreProfileRequest $request): RedirectResponse
    {
        $dto = StoreProfileDTO::fromArray(
            data: $request->validated(),
            logo: $request->file('store_logo'),
            banner: $request->file('store_banner')
        );

        $this->userProfileService->updateStoreProfile(auth()->user(), $dto);

        return redirect()->route('profile.edit', ['tab' => 'store'])
            ->with('success', 'Profil toko penjualan Anda berhasil diperbarui!');
    }
}
