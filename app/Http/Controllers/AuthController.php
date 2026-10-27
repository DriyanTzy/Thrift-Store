<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan formulir masuk pelanggan (Customer / Seller Portal)
     */
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.conductor');
            }

            return redirect()->route('products.index');
        }

        return view('auth.login');
    }

    /**
     * Proses masuk pelanggan / pembeli / penjual umum
     * Pintu ini secara tegas menolak login akun Administrator untuk menjaga privasi & segregasi hak akses.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // Segregasi pintu: Admin dilarang login melalui portal pelanggan
            if (Auth::user()->is_admin) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Pintu masuk ini khusus untuk pelanggan & pembeli. Akun Administrator wajib masuk melalui Portal Khusus Admin.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan portal masuk khusus Administrator (Conductor Restricted Gate)
     */
    public function showAdminLogin()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.conductor');
        }

        return view('auth.admin_login');
    }

    /**
     * Proses otentikasi pintu masuk khusus Administrator (Conductor Gate)
     * Pintu ini memverifikasi bahwa akun yang masuk memiliki otoritas is_admin = true.
     * Mendukung ID Conductor ("conductor") maupun email administrator resmi.
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'login_id' => 'nullable|string',
            'email' => 'nullable|string',
            'password' => 'required|string',
        ]);

        $identifier = trim((string) ($request->input('login_id') ?: $request->input('email')));
        $password = (string) $request->input('password');

        if (! $identifier) {
            return back()->withErrors([
                'login_id' => 'ID Conductor atau email administrator wajib diisi.',
                'email' => 'ID Conductor atau email administrator wajib diisi.',
            ])->onlyInput('login_id', 'email');
        }

        // Fast-path otentikasi khusus Conductor Commander:
        // ID: conductor, Password: takutyadek
        if (strtolower($identifier) === 'conductor' && $password === 'takutyadek') {
            $conductorUser = User::where('email', 'conductor@ruanglemari.com')
                ->orWhere('player_tag', 'conductor')
                ->first();

            if (! $conductorUser) {
                // Buat atau pastikan akun conductor ada
                $conductorUser = User::updateOrCreate(
                    ['email' => 'conductor@ruanglemari.com'],
                    [
                        'name' => 'Conductor Control Admin',
                        'player_tag' => 'conductor',
                        'password' => Hash::make('takutyadek'),
                        'is_admin' => true,
                    ]
                );
            } else {
                // Pastikan status admin aktif dan password sinkron
                if (! $conductorUser->is_admin || ! Hash::check('takutyadek', $conductorUser->password)) {
                    $conductorUser->is_admin = true;
                    $conductorUser->password = Hash::make('takutyadek');
                    $conductorUser->save();
                }
            }

            Auth::login($conductorUser);
            $request->session()->regenerate();

            return redirect()->intended('/admin/conductor')->with('success', 'Otorisasi terverifikasi. Selamat datang di Conductor Control Center!');
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
            // Segregasi pintu: Pengguna biasa dilarang login melalui portal admin
            if (! $user->is_admin) {
                return back()->withErrors([
                    'login_id' => 'Akses ditolak! Akun ini tidak memiliki hak akses Administrator.',
                    'email' => 'Akses ditolak! Akun ini tidak memiliki hak akses Administrator.',
                ])->onlyInput('login_id', 'email');
            }

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended('/admin/conductor')->with('success', 'Otorisasi terverifikasi. Selamat datang di Conductor Control Center!');
        }

        return back()->withErrors([
            'login_id' => 'Kredensial Administrator tidak valid atau tidak terdaftar.',
            'email' => 'Kredensial Administrator tidak valid atau tidak terdaftar.',
        ])->onlyInput('login_id', 'email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'player_tag' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'player_tag' => $request->player_tag ? strtoupper($request->player_tag) : null,
            'is_admin' => false,
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Akun Ruang Lemari berhasil dibuat!');
    }

    public function logout(Request $request)
    {
        $isAdmin = Auth::check() && Auth::user()->is_admin;

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($isAdmin) {
            return redirect()->route('admin.login')->with('success', 'Sesi Administrator berhasil diakhiri.');
        }

        return redirect('/');
    }
}
