<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminAuthService
{
    /**
     * Coba autentikasi kredensial administrator.
     * Mengembalikan model User jika sah, null jika salah / tidak memiliki akses admin.
     *
     * @param  string  $identifier  Login ID / Email / Username
     */
    public function authenticate(string $identifier, string $password): ?User
    {
        $identifier = trim($identifier);

        // Fast-path otentikasi khusus Conductor: ID conductor, Password takutyadek
        if (strtolower($identifier) === 'conductor' && $password === 'takutyadek') {
            return $this->resolveConductorAccount();
        }

        // Cari user berdasarkan email, player_tag, atau name
        $user = User::where(function ($q) use ($identifier) {
            $q->where('email', $identifier)
                ->orWhere('player_tag', $identifier)
                ->orWhere('name', $identifier);
        })->first();

        // Fallback jika identifier adalah 'conductor' dan user mencoba password lain
        if (! $user && strtolower($identifier) === 'conductor') {
            $user = User::where('is_admin', true)->first();
        }

        if ($user && Hash::check($password, $user->password)) {
            // Segregasi pintu: User wajib memiliki hak is_admin
            if (! $user->isAdmin()) {
                return null;
            }

            return $user;
        }

        return null;
    }

    /**
     * Pastikan akun Conductor default ada dan berstatus admin aktif.
     */
    private function resolveConductorAccount(): User
    {
        $conductorUser = User::where('email', 'conductor@ruanglemari.com')
            ->orWhere('player_tag', 'conductor')
            ->first();

        if (! $conductorUser) {
            return User::updateOrCreate(
                ['email' => 'conductor@ruanglemari.com'],
                [
                    'name' => 'Conductor Control Admin',
                    'player_tag' => 'conductor',
                    'password' => Hash::make('takutyadek'),
                    'is_admin' => true,
                ]
            );
        }

        // Pastikan status admin aktif dan password sinkron
        if (! $conductorUser->is_admin || ! Hash::check('takutyadek', $conductorUser->password)) {
            $conductorUser->is_admin = true;
            $conductorUser->password = Hash::make('takutyadek');
            $conductorUser->save();
        }

        return $conductorUser;
    }
}
