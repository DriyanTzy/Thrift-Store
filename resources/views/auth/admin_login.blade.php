<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Conductor — Ruang Lemari</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #F8FAFC;
            color: #0F172A;
            padding: 1.5rem;
            -webkit-font-smoothing: antialiased;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 20px;
            padding: 2.25rem 2rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-box {
            width: 52px;
            height: 52px;
            background: #0F172A;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #22C55E;
            font-size: 1.35rem;
            font-weight: 900;
            margin-bottom: 1rem;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }

        .login-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.3px;
        }

        .login-subtitle {
            font-size: 0.85rem;
            color: #64748B;
            margin-top: 0.25rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.45rem;
        }

        .input-wrap {
            position: relative;
        }

        .form-input {
            width: 100%;
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            border-radius: 10px;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            font-size: 0.9rem;
            color: #0F172A;
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
        }

        .form-input:focus {
            background: #FFFFFF;
            border-color: #0F172A;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
        }

        .input-icon {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 0.85rem;
        }

        .toggle-btn {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            font-size: 0.9rem;
            padding: 0;
        }

        .toggle-btn:hover {
            color: #0F172A;
        }

        .submit-btn {
            width: 100%;
            background: #0F172A;
            color: #FFFFFF;
            border: none;
            border-radius: 10px;
            padding: 0.85rem;
            font-size: 0.92rem;
            font-weight: 800;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            margin-top: 1.5rem;
        }

        .submit-btn:hover {
            background: #1E293B;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #64748B;
            font-size: 0.82rem;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            color: #0F172A;
            text-decoration: underline;
        }

        .error-alert {
            background: #FEE2E2;
            border: 1px solid #FCA5A5;
            color: #B91C1C;
            padding: 0.65rem 0.85rem;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .success-alert {
            background: #DCFCE7;
            border: 1px solid #86EFAC;
            color: #166534;
            padding: 0.65rem 0.85rem;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    
    <div class="brand-header">
        <img src="{{ asset('logo.png') }}" alt="Ruang Lemari" style="height: 48px; width: auto; object-fit: contain; margin-bottom: 0.5rem;">
        <h1 class="login-title">Control Panel Admin</h1>
        <p class="login-subtitle">Semua Langanan dalam satu tempat</p>
    </div>

    @if(session('success'))
        <div class="success-alert">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="error-alert">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->has('email') || $errors->has('login_id'))
        <div class="error-alert">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ $errors->first('login_id') ?: $errors->first('email') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST" autocomplete="off">
        @csrf

        <div class="form-group">
            <label class="form-label" for="login_id">ID Administrator</label>
            <div class="input-wrap">
                <i class="fa-solid fa-user-shield input-icon"></i>
                <input type="text"
                       id="login_id"
                       name="login_id"
                       class="form-input"
                       value="{{ old('login_id') }}"
                       placeholder="Masukkan ID Anda"
                       required
                       autofocus>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-wrap">
                <i class="fa-solid fa-lock input-icon"></i>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-input"
                       placeholder="Masukkan password"
                       required>
                <button type="button" class="toggle-btn" id="toggle-password" title="Lihat password">
                    <i class="fa-regular fa-eye" id="toggle-icon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="submit-btn">
            Masuk Control Panel <i class="fa-solid fa-arrow-right"></i>
        </button>
    </form>

    <a href="{{ route('products.index') }}" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda Toko
    </a>

</div>

<script>
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
