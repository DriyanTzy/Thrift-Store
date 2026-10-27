<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Ruang Lemari</title>
    <meta name="description" content="Login ke Ruang Lemari dan mulai belanja barang thrifting & preloved fashion terpercaya.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --ts-dark: #0F172A;
            --ts-dark-mid: #1E293B;
            --ts-green: #22C55E;
            --ts-green-dark: #16A34A;
            --ts-green-light: #DCFCE7;
            --ts-text: #0F172A;
            --ts-text-muted: #64748B;
            --ts-text-light: #94A3B8;
            --ts-border: #E2E8F0;
            --ts-bg: #F8FAFC;
            --ts-white: #FFFFFF;
            --ts-red: #EF4444;
        }

        body {
            font-family: 'Inter', 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: var(--ts-bg);
            -webkit-font-smoothing: antialiased;
        }

        /* ============================
           LEFT PANEL — Dark Slate
        ============================ */
        .login-left {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem;
            overflow: hidden;
            min-height: 100vh;
            background: var(--ts-dark);
        }

        .login-left-bg {
            position: absolute;
            inset: 0;
            background-image: url('https://images.unsplash.com/photo-1558618666-fcd25c85f82e?q=80&w=1400&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            opacity: 0.25;
            z-index: 0;
        }

        .login-left-gradient {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(15, 23, 42, 0.4) 0%,
                rgba(15, 23, 42, 0.6) 50%,
                rgba(15, 23, 42, 0.92) 100%
            );
            z-index: 1;
        }

        .login-left-content {
            position: relative;
            z-index: 2;
        }

        .login-brand-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid rgba(34, 197, 94, 0.4);
            color: #4ADE80;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.7rem;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.25rem;
        }

        .login-headline {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 2.4rem;
            font-weight: 800;
            color: #FFFFFF;
            line-height: 1.15;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .login-subtext {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.6);
            line-height: 1.65;
            max-width: 380px;
            margin-bottom: 2.5rem;
        }

        .login-features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .login-feature {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            padding: 1rem;
            transition: background 0.2s;
        }

        .login-feature:hover {
            background: rgba(255,255,255,0.1);
        }

        .login-feature-icon {
            font-size: 1.1rem;
            color: #4ADE80;
            margin-bottom: 0.5rem;
        }

        .login-feature-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 0.25rem;
        }

        .login-feature-desc {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.5);
            line-height: 1.5;
        }

        /* ============================
           RIGHT PANEL — White Form
        ============================ */
        .login-right {
            width: 480px;
            flex-shrink: 0;
            background: var(--ts-white);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-y: auto;
        }

        .login-form-header {
            background: var(--ts-dark);
            padding: 2.5rem 2.5rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .login-logo-circle {
            width: 56px;
            height: 56px;
            background: var(--ts-green);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--ts-dark);
            font-family: 'Plus Jakarta Sans', serif;
            margin-bottom: 0.875rem;
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.35);
        }

        .login-form-app-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: #FFFFFF;
            margin-bottom: 0.2rem;
        }

        .login-form-app-sub {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.7);
        }

        .login-form-body {
            flex: 1;
            padding: 2rem 2.5rem 2.5rem;
        }

        /* Form Fields */
        .ts-form-group {
            margin-bottom: 1.25rem;
        }

        .ts-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--ts-text);
            margin-bottom: 0.45rem;
        }

        .ts-input-wrap {
            position: relative;
        }

        .ts-input {
            width: 100%;
            background: var(--ts-bg);
            border: 1.5px solid var(--ts-border);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            color: var(--ts-text);
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .ts-input:focus {
            border-color: var(--ts-green);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.12);
            background: var(--ts-white);
        }

        .ts-input::placeholder {
            color: var(--ts-text-light);
        }

        .ts-input-toggle {
            position: absolute;
            right: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--ts-text-light);
            cursor: pointer;
            font-size: 0.9rem;
            padding: 0;
            transition: color 0.2s;
        }

        .ts-input-toggle:hover {
            color: var(--ts-green);
        }

        .ts-input.has-toggle {
            padding-right: 2.5rem;
        }

        .ts-field-error {
            color: var(--ts-red);
            font-size: 0.78rem;
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* Remember & Forgot */
        .ts-form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .ts-checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--ts-text-muted);
            cursor: pointer;
        }

        .ts-checkbox {
            width: 16px;
            height: 16px;
            accent-color: var(--ts-green);
            cursor: pointer;
        }

        .ts-forgot-link {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--ts-green);
            text-decoration: none;
            transition: color 0.2s;
        }

        .ts-forgot-link:hover {
            color: var(--ts-green-dark);
            text-decoration: underline;
        }

        /* Submit Button */
        .ts-submit-btn {
            width: 100%;
            background: var(--ts-dark);
            color: #FFF;
            border: none;
            border-radius: 10px;
            padding: 0.85rem;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.35);
            margin-bottom: 1.5rem;
        }

        .ts-submit-btn:hover {
            background: #1E293B;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.45);
        }

        .ts-submit-btn:active {
            transform: translateY(0);
        }

        /* Divider */
        .ts-divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .ts-divider::before, .ts-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--ts-border);
        }

        .ts-divider span {
            font-size: 0.78rem;
            color: var(--ts-text-light);
            white-space: nowrap;
        }

        /* OAuth Buttons */
        .ts-oauth-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1.75rem;
        }

        .ts-oauth-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.65rem;
            border: 1.5px solid var(--ts-border);
            border-radius: 10px;
            background: var(--ts-white);
            color: var(--ts-text-muted);
            font-size: 0.85rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .ts-oauth-btn:hover {
            border-color: #CBD5E1;
            background: var(--ts-bg);
            color: var(--ts-text);
        }

        /* Sign up link */
        .ts-signup-text {
            text-align: center;
            font-size: 0.85rem;
            color: var(--ts-text-muted);
        }

        .ts-signup-text a {
            color: var(--ts-green);
            font-weight: 700;
            text-decoration: none;
        }

        .ts-signup-text a:hover {
            text-decoration: underline;
        }



        /* Responsive */
        @media (max-width: 900px) {
            .login-left { display: none; }
            .login-right { width: 100%; }
        }
    </style>
</head>
<body>

<div class="login-left">
    <div class="login-left-bg"></div>
    <div class="login-left-gradient"></div>
    <div class="login-left-content">
        <div class="login-brand-tag">
            <i class="fa-solid fa-recycle"></i> RUANG LEMARI MARKETPLACE
        </div>
        <h1 class="login-headline">Temukan Gaya Vintage<br>Favoritmu.</h1>
        <p class="login-subtext">Marketplace thrifting & preloved fashion terpercaya. Jual-beli jaket vintage, sneakers OG, kaos band, dan koleksi thrift terbaik dari seluruh Indonesia.</p>

        <div class="login-features">
            <div class="login-feature">
                <div class="login-feature-icon"><i class="fa-solid fa-shirt"></i></div>
                <div class="login-feature-title">Preloved Original</div>
                <div class="login-feature-desc">Koleksi vintage authentic dari brand-brand ternama dunia, 100% original guaranteed.</div>
            </div>
            <div class="login-feature">
                <div class="login-feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="login-feature-title">Transaksi Aman</div>
                <div class="login-feature-desc">Sistem pembayaran terpercaya dengan bukti transaksi & riwayat pembelian lengkap.</div>
            </div>
            <div class="login-feature">
                <div class="login-feature-icon"><i class="fa-solid fa-store"></i></div>
                <div class="login-feature-title">Buka Toko Gratis</div>
                <div class="login-feature-desc">Mulai jualan barang thrift Anda sendiri dengan fitur etalase toko yang mudah digunakan.</div>
            </div>
        </div>
    </div>
</div>

<div class="login-right">
    <!-- Dark header -->
    <div class="login-form-header">
        <div class="login-logo-circle">RL</div>
        <div class="login-form-app-name">Ruang Lemari</div>
        <div class="login-form-app-sub">Masuk ke akun Anda</div>
    </div>

    <!-- Form body -->
    <div class="login-form-body">
        <form id="login-form" action="{{ route('login') }}" method="POST">
            @csrf

            <div class="ts-form-group">
                <label class="ts-label" for="email">Email</label>
                <input type="email"
                       id="email"
                       name="email"
                       class="ts-input"
                       value="{{ old('email') }}"
                       placeholder="nama@email.com"
                       required
                       autocomplete="email">
                @error('email')
                    <div class="ts-field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="ts-form-group">
                <label class="ts-label" for="password">Password</label>
                <div class="ts-input-wrap">
                    <input type="password"
                           id="password"
                           name="password"
                           class="ts-input has-toggle"
                           placeholder="••••••••••••"
                           required
                           autocomplete="current-password">
                    <button type="button" class="ts-input-toggle" id="toggle-password">
                        <i class="fa-regular fa-eye" id="toggle-icon"></i>
                    </button>
                </div>
            </div>

            <div class="ts-form-options">
                <label class="ts-checkbox-label">
                    <input type="checkbox" name="remember" class="ts-checkbox" id="remember">
                    Ingat saya
                </label>
                <a href="#" class="ts-forgot-link">Lupa password?</a>
            </div>

            <button type="submit" class="ts-submit-btn" id="login-btn">
                <i class="fa-solid fa-right-to-bracket" style="margin-right: 0.4rem;"></i> Masuk
            </button>
        </form>

        <div class="ts-divider"><span>Atau masuk dengan</span></div>

        <div class="ts-oauth-row">
            <a href="#" class="ts-oauth-btn">
                <svg width="16" height="16" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                Google
            </a>
            <a href="#" class="ts-oauth-btn">
                <i class="fa-brands fa-apple" style="font-size: 1.1rem;"></i>
                Apple
            </a>
        </div>

        <div class="ts-signup-text">
            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>

    </div>
</div>

<script>
    // Toggle password visibility
    const toggleBtn = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggle-icon');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });
    }
</script>
</body>
</html>
