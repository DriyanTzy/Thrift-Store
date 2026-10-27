<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan akun administrator Conductor dengan id: conductor dan password: takutyadek siap digunakan
        User::updateOrCreate(
            ['email' => 'conductor@ruanglemari.com'],
            [
                'name' => 'Conductor Control Admin',
                'player_tag' => 'conductor',
                'password' => Hash::make('takutyadek'),
                'is_admin' => true,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
