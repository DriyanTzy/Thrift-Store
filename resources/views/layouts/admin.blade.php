<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — Ruang Lemari</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --rp-purple: #7C3AED;
            --rp-purple-hover: #6D28D9;
            --admin-bg: #F8FAFC;
            --sidebar-bg: #FFFFFF;
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background-color: var(--admin-bg);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        aside {
            width: 230px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 50;
        }

        .sidebar-brand {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 0.65rem;
            height: 54px;
        }

        .sidebar-brand .logo-badge {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #0F172A;
            color: #22C55E;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            box-shadow: 0 3px 8px rgba(15, 23, 42, 0.2);
        }

        .sidebar-menu {
            padding: 1rem 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            flex: 1;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.55rem 0.85rem;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.84rem;
            border-radius: 8px;
            transition: all 0.15s;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(15, 23, 42, 0.06);
            color: #0F172A;
        }

        /* Main Admin Wrapper */
        .admin-main {
            margin-left: 230px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Compact Slim Header (Anti Segede Gaban) */
        header.admin-header {
            background: #FFFFFF;
            border-bottom: 1px solid var(--border-color);
            padding: 0 1.5rem;
            height: 54px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .content-area {
            padding: 1.25rem 1.5rem;
            flex: 1;
        }

        /* Cards & Components */
        .admin-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }

        .btn-admin {
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-yellow {
            background: var(--rp-purple);
            color: #FFF;
        }

        .btn-yellow:hover {
            background: var(--rp-purple-hover);
        }

        .btn-danger {
            background: #EF4444;
            color: #FFF;
        }

        .btn-secondary {
            background: #F1F5F9;
            color: #334155;
            border: 1px solid #E2E8F0;
        }

        /* Table */
        .sc-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 0.5rem;
        }

        .sc-table th, .sc-table td {
            padding: 0.9rem 1rem;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        .sc-table th {
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 800;
            background: #F8FAFC;
        }

        .sc-table tbody tr {
            transition: background 0.15s ease;
        }

        .sc-table tbody tr:hover {
            background: #F8FAFC;
        }

        .status-badge {
            padding: 0.2rem 0.55rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            display: inline-block;
            white-space: nowrap;
            letter-spacing: 0.3px;
        }

        .status-paid, .status-completed, .status-valid {
            background: #DCFCE7;
            color: #15803D;
            border: 1px solid #86EFAC;
        }

        .status-pending, .status-processing {
            background: #FEF3C7;
            color: #B45309;
            border: 1px solid #FDE68A;
        }

        .status-failed, .status-cancelled, .status-invalid {
            background: #FEE2E2;
            color: #B91C1C;
            border: 1px solid #FECACA;
        }

        /* Laravel Pagination Normalization in Admin */
        nav[role="navigation"] svg {
            width: 14px !important;
            height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
            display: inline-block !important;
            vertical-align: middle;
        }
        nav[role="navigation"] > div:first-child {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        nav[role="navigation"] a,
        nav[role="navigation"] span {
            font-size: 0.8rem !important;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Admin Sidebar -->
    <aside>
        <div class="sidebar-brand">
            <img src="{{ asset('logo.png') }}" alt="Ruang Lemari" style="height: 38px; width: auto; object-fit: contain;">
            <div>
                <span style="font-weight: 800; font-size: 1.05rem; color: #0F172A; display: block; line-height: 1.2;">Ruang Lemari</span>
                <span style="font-size: 0.7rem; font-weight: 700; color: #7C3AED; text-transform: uppercase;">Admin Control</span>
            </div>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.conductor') }}" id="conductor" class="sidebar-link {{ request()->routeIs('admin.conductor') ? 'active' : '' }}" style="{{ request()->routeIs('admin.conductor') ? 'background: rgba(34, 197, 94, 0.15); color: #16A34A; font-weight: 800;' : '' }}">
                <i class="fa-solid fa-sliders" style="color: #22C55E;"></i> Conductor Control
            </a>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i> Dashboard Overview
            </a>
            <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fa-solid fa-box"></i> Kelola Produk
            </a>
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group"></i> Kelola Kategori
            </a>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fa-solid fa-cart-shopping"></i> Kelola Pesanan
            </a>
            <a href="{{ route('admin.conductor') }}#eventVoucherSection" class="sidebar-link">
                <i class="fa-solid fa-ticket" style="color: #EC4899;"></i> Tab Event & Voucher
            </a>

            <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                <a href="{{ route('products.index') }}" class="sidebar-link" target="_blank">
                    <i class="fa-solid fa-store"></i> Lihat Toko Utama
                </a>
                <form action="{{ route('logout') }}" method="POST" style="margin-top: 0.5rem;">
                    @csrf
                    <button type="submit" class="sidebar-link" style="width: 100%; border: none; background: none; cursor: pointer; text-align: left; color: #EF4444;">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout Admin
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main">
        <header class="admin-header">
            <div style="display: flex; align-items: center; gap: 0.6rem;">
                <span style="font-size: 0.95rem; font-weight: 800; color: #0F172A;">Ruang Lemari Conductor</span>
                <span style="font-size: 0.68rem; font-weight: 800; background: #DCFCE7; color: #15803D; padding: 0.15rem 0.5rem; border-radius: 20px; border: 1px solid #86EFAC;">
                    <i class="fa-solid fa-circle-check"></i> System Active
                </span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span style="font-size: 0.8rem; color: #475569; font-weight: 700;">
                    <i class="fa-solid fa-user-shield" style="color: #16A34A;"></i> {{ auth()->user()->name }} (Admin)
                </span>
            </div>
        </header>

        <div class="content-area">
            @if(session('success'))
                <div style="padding: 1rem; background: rgba(22, 163, 74, 0.1); border: 1px solid #16A34A; color: #16A34A; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 600;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>

