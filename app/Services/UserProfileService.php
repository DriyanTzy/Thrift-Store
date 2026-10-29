<?php

namespace App\Services;

use App\DTO\PersonalProfileDTO;
use App\DTO\StoreProfileDTO;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserProfileService
{
    /**
     * Perbarui data profil pribadi pengguna beserta avatar via PersonalProfileDTO.
     */
    public function updatePersonalProfile(User $user, PersonalProfileDTO $dto): User
    {
        $attributes = $dto->toModelAttributes();

        if ($dto->avatar) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $attributes['avatar'] = $dto->avatar->store('avatars', 'public');
        }

        $user->update($attributes);

        return $user;
    }

    /**
     * Perbarui data etalase toko (brand, kontak toko, logo, banner) via StoreProfileDTO.
     */
    public function updateStoreProfile(User $user, StoreProfileDTO $dto): User
    {
        $attributes = $dto->toModelAttributes();

        if ($dto->logo) {
            if ($user->store_logo && Storage::disk('public')->exists($user->store_logo)) {
                Storage::disk('public')->delete($user->store_logo);
            }
            $attributes['store_logo'] = $dto->logo->store('stores', 'public');
        }

        if ($dto->banner) {
            if ($user->store_banner && Storage::disk('public')->exists($user->store_banner)) {
                Storage::disk('public')->delete($user->store_banner);
            }
            $attributes['store_banner'] = $dto->banner->store('stores', 'public');
        }

        $user->update($attributes);

        return $user;
    }
}
