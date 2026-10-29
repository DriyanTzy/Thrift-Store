<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\Auth\AdminAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private readonly AdminAuthService $adminAuthService
    ) {}

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
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            // Segregasi pintu: Admin dilarang login melalui portal pelanggan
            if (Auth::user()->isAdmin()) {
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
    public function showAdminLogin(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.conductor');
        }

        return view('auth.admin_login');
    }

    /**
     * Proses otentikasi pintu masuk khusus Administrator (Conductor Gate)
     * Pintu ini memverifikasi bahwa akun yang masuk memiliki otoritas is_admin = true.
     * Mendukung ID Conductor ("conductor") maupun email administrator resmi.
     */
    public function adminLogin(AdminLoginRequest $request): RedirectResponse
    {
        $identifier = $request->getIdentifier();
        $password = (string) $request->input('password');

        if (! $identifier) {
            return back()->withErrors([
                'login_id' => 'ID Conductor atau email administrator wajib diisi.',
                'email' => 'ID Conductor atau email administrator wajib diisi.',
            ])->onlyInput('login_id', 'email');
        }

        // Verifikasi kredensial Administrator via AdminAuthService
        $user = $this->adminAuthService->authenticate($identifier, $password);

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended('/admin/conductor')->with('success', 'Otorisasi terverifikasi. Selamat datang di Conductor Control Center!');
        }

        return back()->withErrors([
            'login_id' => 'Kredensial Administrator tidak valid atau tidak terdaftar.',
            'email' => 'Kredensial Administrator tidak valid atau tidak terdaftar.',
        ])->onlyInput('login_id', 'email');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'player_tag' => ! empty($validated['player_tag']) ? strtoupper($validated['player_tag']) : null,
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
