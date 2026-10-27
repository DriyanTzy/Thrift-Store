<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Ruang Lemari</title>
    <meta name="description" content="Buat akun Ruang Lemari dan nikmati kemudahan jual beli barang thrifting & preloved authentic.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --ts-dark: #0F172A;
            --ts-green: #22C55E;
            --ts-green-dark: #16A34A;
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
            background: var(--ts-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            -webkit-font-smoothing: antialiased;
        }

        .register-card {
            background: var(--ts-white);
            border-radius: 24px;
            box-shadow: 0 12px 40px rgba(15,23,42,0.08), 0 2px 8px rgba(0,0,0,0.03);
            border: 1px solid var(--ts-border);
            width: 100%;
            max-width: 460px;
            overflow: hidden;
        }

        /* Card Header */
        .register-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2.5rem 2.5rem 1.75rem;
            text-align: center;
            background: var(--ts-dark);
            color: #FFF;
        }

        .register-logo {
            width: 58px;
            height: 58px;
            background: var(--ts-green);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--ts-dark);
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin-bottom: 0.85rem;
            box-shadow: 0 6px 20px rgba(34,197,94,0.35);
        }

        .register-app-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: #FFFFFF;
            margin-bottom: 0.2rem;
        }

        .register-subtitle {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.7);
        }

        /* Form body */
        .register-body {
            padding: 2rem 2.5rem 2.5rem;
        }

        .ts-form-group {
            margin-bottom: 1.15rem;
        }

        .ts-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--ts-text);
            margin-bottom: 0.4rem;
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
            font-size: 0.88rem;
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

        .ts-input.has-toggle {
            padding-right: 2.5rem;
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

        .ts-field-error {
            color: var(--ts-red);
            font-size: 0.75rem;
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .ts-submit-btn {
            width: 100%;
            background: var(--ts-dark);
            color: #FFF;
            border: none;
            border-radius: 10px;
            padding: 0.85rem;
            font-size: 0.95rem;
            font-weight: 800;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.25);
            margin-top: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .ts-submit-btn:hover {
            background: #1E293B;
            transform: translateY(-1px);
        }

        .ts-login-text {
            text-align: center;
            font-size: 0.85rem;
            color: var(--ts-text-muted);
        }

        .ts-login-text a {
            color: var(--ts-green);
            font-weight: 700;
            text-decoration: none;
        }

        .ts-login-text a:hover {
            text-decoration: underline;
        }

        .ts-divider {
            height: 1px;
            background: var(--ts-border);
            margin: 1.25rem 0;
        }
    </style>
</head>
<body>

<div class="register-card">
    <!-- Header -->
    <div class="register-header">
        <div class="register-logo">RL</div>
        <div class="register-app-name">Ruang Lemari</div>
        <div class="register-subtitle">Buat Akun Pembeli / Penjual</div>
    </div>

    <!-- Form -->
    <div class="register-body">
        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="ts-form-group">
                <label class="ts-label" for="name">Nama Lengkap</label>
                <input type="text"
                       id="name"
                       name="name"
                       class="ts-input"
                       value="{{ old('name') }}"
                       placeholder="Nama lengkap kamu"
                       required
                       autocomplete="name">
                @error('name')
                    <div class="ts-field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

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
                <label class="ts-label" for="player_tag">Username / Toko (Opsional)</label>
                <input type="text"
                       id="player_tag"
                       name="player_tag"
                       class="ts-input"
                       value="{{ old('player_tag') }}"
                       placeholder="Contoh: ThriftVintage_ID">
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
                           autocomplete="new-password">
                    <button type="button" class="ts-input-toggle" id="toggle-pw">
                        <i class="fa-regular fa-eye" id="toggle-icon-pw"></i>
                    </button>
                </div>
                @error('password')
                    <div class="ts-field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="ts-form-group">
                <label class="ts-label" for="password_confirmation">Konfirmasi Password</label>
                <div class="ts-input-wrap">
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           class="ts-input has-toggle"
                           placeholder="••••••••••••"
                           required
                           autocomplete="new-password">
                    <button type="button" class="ts-input-toggle" id="toggle-pw2">
                        <i class="fa-regular fa-eye" id="toggle-icon-pw2"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="ts-submit-btn">
                Daftar Akun Sekarang
            </button>
        </form>

        <div class="ts-divider"></div>

        <div class="ts-login-text">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>
</div>

<script>
    function setupToggle(btnId, inputId, iconId) {
        const btn = document.getElementById(btnId);
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!btn) return;
        btn.addEventListener('click', function() {
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    }
    setupToggle('toggle-pw', 'password', 'toggle-icon-pw');
    setupToggle('toggle-pw2', 'password_confirmation', 'toggle-icon-pw2');
</script>
</body>
</html>
