<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('conductor:setup', function () {
    User::updateOrCreate(
        ['email' => 'conductor@ruanglemari.com'],
        [
            'name' => 'Conductor Control Admin',
            'player_tag' => 'conductor',
            'password' => Hash::make('takutyadek'),
            'is_admin' => true,
        ]
    );
    $this->info("Conductor Admin ready: ID='conductor', Password='takutyadek'");
})->purpose('Setup conductor admin credentials');
